import 'promise-polyfill';
import 'whatwg-fetch';
import React, { Fragment, useState } from 'react';
import Select2 from 'react-select2-wrapper';

const Pagination = ({ id = '', count, limit, skip, setSkip }) => {
  const pageCount = Math.ceil(count / limit);
  const firstPage = 0;
  const lastPage = pageCount - 1;
  const currentPage = Math.floor((skip + 1) / limit);
  const prevSkip = Math.max((currentPage - 1) * limit, 0);
  const nextSkip = Math.min((currentPage + 1) * limit, (pageCount - 1) * limit);

  return (
    <div className="pagination d-flex justify-content-between">
      <button
        disabled={currentPage === firstPage}
        className="btn btn-sm btn-link text-dark order-first pagination__prev"
        onClick={() => setSkip(prevSkip)}
      >
        Prev
      </button>
      <button
        disabled={currentPage === lastPage}
        className="btn btn-sm btn-link text-dark order-last pagination__next"
        onClick={() => setSkip(nextSkip)}
      >
        Next
      </button>
      <ol className="pagination__pages list-inline mb-0 d-none d-sm-block">
        {Array.from({ length: pageCount }).map((u, i) => (
          <li className="list-inline-item" key={`${id}-page-${i + 1}-link`}>
            <button
              disabled={currentPage === i}
              className={`btn btn-sm btn-link px-1 pagination__page text-${
                currentPage === i ? 'pink-dark' : 'dark'
              }`}
              onClick={() => setSkip(i * limit)}
              style={{ opacity: 1, fontWeight: currentPage === i ? 'bold' : 'normal' }}
            >
              {i + 1}
            </button>
          </li>
        ))}
      </ol>
    </div>
  );
};

const filterJobs = (filters = {}, jobOpenings = []) => {
  const { search = '' } = filters;
  const filterKeys = [
    'remote_friendly',
    'description',
    'locations',
    'industries',
    'company',
    'job_title',
    'job_types',
    'experience_levels'
  ];

  const filterValues = {};

  const removeExp = /(<([^>]+)>|[^a-zA-Z0-9])/gi;
  const searchStr = search
    .trim()
    .toLowerCase()
    .replace(removeExp, '');

  const filteredJobs = jobOpenings.filter(jobOpening => {
    let text = '';

    const filterMatch = filterKeys.reduce((isMatch, key) => {
      const filter = filters[key];
      const filterArr = [].concat(filter || []);
      const { [key]: jobValue } = jobOpening;
      const jobValueArr = [].concat(jobValue || []);

      if (searchStr) {
        text += jobValueArr.join(' ');
      }

      if (key !== 'description') {
        if (!filterValues[key]) {
          filterValues[key] = new Set();
        }

        jobValueArr.forEach(val => filterValues[key].add(val));

        if (filterArr.length) {
          return isMatch && jobValueArr.filter(val => filterArr.includes(val)).length;
        }
      }
      return isMatch;
    }, true);

    const searchMatch =
      !searchStr ||
      text
        .toLowerCase()
        .replace(removeExp, '')
        .indexOf(searchStr) !== -1;

    return filterMatch && searchMatch;
  });

  return [filteredJobs, filterValues];
};

const experienceValue = x => {
  switch (x) {
    case 'Director':
      return 1;
    case 'Senior':
      return 2;
    case 'Mid Level':
      return 3;
    case 'Entry Level':
      return 4;
    case 'Internship':
      return 5;
    default:
      return 0;
  }
};

const JobTable = ({ jobOpenings = [], limit = 10, tableId = '', tableClassName = '' }) => {
  const [skip, setSkip] = useState(0);
  const [activeJob, setActiveJob] = useState(null);
  const [filters, setFilters] = useState({});
  const {
    // search = '',
    remote_friendly: remoteFilter = false,
    // locations: locationsFilter = [],
    job_types: jobTypesFilter = [],
    experience_levels: experienceLevelsFilter = []
  } = filters;
  const permaLink = document.location.href;

  const [filteredJobs, filterValues] = filterJobs(filters, jobOpenings);

  const {
    locations: locationOptions,
    job_types: jobTypeOptions,
    experience_levels: experienceLevelOptions
  } = filterValues;

  // Remove 'Remote' from the location filter
  locationOptions.delete('Remote');

  return (
    <div id={tableId} className={tableClassName}>
      <div
        className="px-4 py-3 py-md-2 mb-5 bg-dark text-white small"
        style={{ borderRadius: '0.5rem' }}
      >
        <div className="form-row">
          {/*
            <div className="col-12 col-sm-4 my-3">
              <label htmlFor="job-search-input">Search</label>
              <input
                id="job-search-input"
                type="text"
                className="form-control form-control-sm"
                value={search}
                onChange={event => setFilters({ ...filters, search: event.target.value })}
              />
            </div>
          */}
          <div className="col-12 col-sm-4 my-3">
            <p>Remote</p>
            <div className="form-check">
              <input
                className="form-check-input"
                type="checkbox"
                value="Remote"
                id="job-remote-filter"
                onChange={event => {
                  const isChecked = event.target.checked;
                  if (isChecked !== remoteFilter) {
                    setFilters({
                      ...filters,
                      remote_friendly: isChecked
                    });
                  }
                }}
              />
              <label
                className="form-check-label"
                htmlFor="job-remote-filter"
                style={{ fontSize: '1.2em' }}
              >
                Remote friendly
              </label>
            </div>
          </div>
          {/*
            <div className="col-12 col-sm-4 my-3">
              <label htmlFor="job-location-filter">Location</label>
              <Select2
                multiple
                data={Array.from(locationOptions).sort((a, b) => {
                  if (a === b) {
                    return 0;
                  }
                  return a < b ? -1 : 1;
                })}
                id="job-location-filter"
                value={locationsFilter}
                onChange={event => {
                  const value = Array.from(event.target.selectedOptions).map(o => o.value);
                  if (value !== locationsFilter) {
                    setFilters({ ...filters, locations: value });
                  }
                }}
                options={{
                  width: '100%'
                }}
              />
            </div>
          */}
          <div className="col-12 col-sm-4 my-3">
            <label htmlFor="job-type-filter">Job Type</label>
            <Select2
              multiple
              data={Array.from(jobTypeOptions).sort()}
              id="job-type-filter"
              value={jobTypesFilter}
              onChange={event => {
                const value = Array.from(event.target.selectedOptions).map(option => option.value);
                if (value !== jobTypesFilter) {
                  setFilters({ ...filters, job_types: value });
                }
              }}
              options={{
                width: '100%'
              }}
            />
          </div>
          <div className="col-12 col-sm-4 my-3">
            <label htmlFor="job-experience-filter">Experience Level</label>
            <Select2
              multiple
              data={Array.from(experienceLevelOptions).sort((a, b) => {
                if (a === b) {
                  return 0;
                }
                return experienceValue(a) < experienceValue(b) ? -1 : 1;
              })}
              id="job-experience-filter"
              value={experienceLevelsFilter}
              onChange={event => {
                const value = Array.from(event.target.selectedOptions).map(option => option.value);
                if (value !== experienceLevelsFilter) {
                  setFilters({ ...filters, experience_levels: value });
                }
              }}
              options={{
                width: '100%'
              }}
            />
          </div>
        </div>
      </div>
      <div className="p-2 border" style={{ borderRadius: '0.5rem' }}>
        <table className="table text-dark small mb-0">
          <thead>
            <tr>
              <th className="job-title border-top-0">Job Title</th>
              <th className="job-company border-top-0">Company</th>
              <th className="job-location border-top-0 d-none d-sm-table-cell">Location</th>
            </tr>
          </thead>
          <tbody>
            {filteredJobs
              .slice(skip, limit + skip)
              .map(
                ({
                  id,
                  posted,
                  description,
                  job_title: jobTitle,
                  company,
                  industries,
                  locations,
                  link,
                  job_types: jobTypes,
                  experience_levels: experienceLevels,
                  contact_email: contactEmail
                }) => {
                  const linkUrl = new URL(link || `mailto:${contactEmail}`);
                  const params = link
                    ? {
                        utm_source: 'daily-carnage',
                        utm_medium: 'web'
                      }
                    : {
                        subject: `${jobTitle} job`,
                        body: `\n\n---\nSent from a job listing at ${permaLink}`
                      };
                  Object.keys(params).forEach(key => linkUrl.searchParams.append(key, params[key]));
                  const url = link
                    ? linkUrl.href
                    : `${linkUrl.protocol}${linkUrl.pathname}${linkUrl.search.replace(/\+/g, ' ')}`;

                  return (
                    <Fragment key={`${tableId}-${id}`}>
                      <tr id={`job-${id}`} className="job">
                        <td className="job-title">
                          <a
                            href={`#job-${id}`}
                            className="no-scroll font-weight-bold text-pink-dark job__view-link"
                            data-job={`${id} - ${company} - ${jobTitle}`}
                            onClick={event => {
                              event.preventDefault();
                              setActiveJob(activeJob === id ? undefined : id);
                            }}
                          >
                            {jobTitle}
                          </a>
                        </td>
                        <td className="job-company">{company}</td>
                        <td className="job-location d-none d-sm-table-cell">
                          {locations ? locations.join(' | ') : '&ndash;'}
                        </td>
                      </tr>
                      <tr className={`job-details ${activeJob === id ? 'is-active' : ''}`}>
                        <td colSpan="3">
                          <div className="row">
                            <div className="job-location col-12 d-sm-none">
                              <p>
                                <strong>Location</strong>
                                <br />
                                {locations ? locations.join(' | ') : '&ndash;'}
                              </p>
                            </div>
                            <div className="job-experience col-sm-4">
                              <p>
                                <strong>Level</strong>
                                <br />
                                {experienceLevels ? experienceLevels.join(' | ') : '&nbsp;'}
                              </p>
                            </div>
                            <div className="job-type col-sm-4">
                              <p>
                                <strong>Type</strong>
                                <br />
                                {jobTypes ? jobTypes.join(' | ') : '&nbsp;'}
                              </p>
                            </div>
                            <div className="job-industry col-sm-4">
                              <p>
                                <strong>Industry</strong>
                                <br />
                                {industries ? industries.join(' | ') : '&ndash;'}
                              </p>
                            </div>
                            <div className="job-description col-md-9">
                              {!description ? null : (
                                // eslint-disable-next-line react/no-danger
                                <div dangerouslySetInnerHTML={{ __html: description }} />
                              )}
                              <p className="small">
                                <em>Posted {posted}</em>
                              </p>
                            </div>
                            <div className="job-apply col-md-3 d-flex flex-column justify-content-end align-items-md-end">
                              <p>
                                <a
                                  className="btn btn-sm btn-dark px-3 job__apply-link"
                                  href={url}
                                  data-job={`${id} - ${company} - ${jobTitle}`}
                                >
                                  Apply Now
                                </a>
                              </p>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </Fragment>
                  );
                }
              )}
          </tbody>
          {filteredJobs.length > limit && (
            <tfoot>
              <tr>
                <td colSpan="3">
                  <Pagination
                    id={`${tableId}-pagination`}
                    count={jobOpenings.length}
                    limit={limit}
                    skip={skip}
                    setSkip={setSkip}
                  />
                </td>
              </tr>
            </tfoot>
          )}
        </table>
      </div>
    </div>
  );
};

export default JobTable;
