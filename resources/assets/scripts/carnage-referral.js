/* eslint-disable no-unused-vars */
import copy from 'copy-to-clipboard';
import 'promise-polyfill';
import serialize from 'form-serialize';
import 'whatwg-fetch';
import React, { PureComponent } from 'react';
import { render } from 'react-dom';

if (window.admin && window.admin.top_referrers) {
  window.admin.referrers = {};
  window.admin.top_referrers.forEach(referrer => {
    const { ID } = referrer;
    window.admin.referrers[ID] = referrer;
  });

  if (window.admin.referred_by) {
    Object.keys(window.admin.referred_by).forEach(key => {
      const referrerId = window.admin.referred_by[key];
      window.admin.referred_by[key] = window.admin.referrers[referrerId];
    });
  }
}

const carnageLogo = require('../images/svg/logo-carnage.svg');
const loading = require('../images/loading.gif');

const TwitterIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="32" height="32">
    <path
      fill="currentColor"
      d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"
    />
  </svg>
);

const FacebookIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 264 512" width="17" height="32">
    <path
      fill="currentColor"
      d="M76.7 512V283H0v-91h76.7v-71.7C76.7 42.4 124.3 0 193.8 0c33.3 0 61.9 2.5 70.2 3.6V85h-48.2c-37.8 0-45.1 18-45.1 44.3V192H256l-11.7 91h-73.6v229"
    />
  </svg>
);

const LinkedInIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448.1 448" width="32" height="32">
    <path
      fill="currentColor"
      d="M100.3 448H7.4V148.9h92.9V448zM53.8 108.1C24.1 108.1 0 83.5 0 53.8S24.1 0 53.8 0s53.8 24.1 53.8 53.8-24.1 54.3-53.8 54.3zM448 448h-92.7V302.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.8V148.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V448h-.1z"
    />
  </svg>
);

const CheckIcon = () => (
  <svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
    <path
      fill="currentColor"
      d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"
    />
  </svg>
);

const EmailIcon = () => (
  <svg
    aria-hidden="true"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 512 384"
    style={{ height: '1.6rem' }}
    width="34"
    height="26"
  >
    <path
      fill="currentColor"
      d="M502.3 126.8c3.9-3.1 9.7-.2 9.7 4.7V336c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V131.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 256c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"
    />
  </svg>
);

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
        className="btn btn-sm btn-link text-white order-first pagination__prev"
        onClick={() => setSkip(prevSkip)}
      >
        Prev
      </button>
      <button
        disabled={currentPage === lastPage}
        className="btn btn-sm btn-link text-white order-last pagination__next"
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
                currentPage === i ? 'pink-dark' : 'white'
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

class UserTable extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {
      skip: 0
    };

    this.setSkip = this.setSkip.bind(this);
  }

  setSkip(skip) {
    this.setState({ skip });
  }

  render() {
    const {
      users = [],
      limit = 10,
      id = '',
      columns = { user: 'User', count: 'Count' }
    } = this.props;
    const { skip } = this.state;

    return (
      <div className="table-responsive">
        <table
          className="table table-hover table-dark table-striped"
          style={{ fontSize: '0.9rem', tableLayout: 'fixed' }}
        >
          <thead>
            <tr>
              {Object.keys(columns).map(key => (
                <th key={`${id}-head-${key}`} className={`${key}-header`}>
                  {columns[key]}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {users
              .slice(skip, limit + skip)
              .map(
                (
                  {
                    ID,
                    referral_id: referralId,
                    user_email: email,
                    user_login: login,
                    user_registered: registered,
                    referral_count: count,
                    referral_rank: rank,
                    referred_by: referredBy,
                    username
                  },
                  index
                ) => (
                  <tr key={`${id}-${login}`}>
                    {Object.keys(columns).map(key => (
                      <td key={`${id}-${login}-${key}`} className={`${key}-cell`}>
                        {(() => {
                          switch (key) {
                            case 'rank':
                              return `${rank}.`;
                            case 'number':
                              return `${skip + index + 1}.`;
                            case 'count':
                              return count;
                            case 'registered':
                              return new Date(registered).toLocaleDateString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: undefined,
                                month: 'short',
                                day: '2-digit',
                                year: undefined
                              });
                            case 'user':
                              return (
                                <a
                                  href={`/carnage-referral-legacy/?u=${referralId}`}
                                  className="text-white"
                                >
                                  {username}
                                </a>
                              );
                            // eslint-disable-next-line no-case-declarations
                            case 'referred_by':
                              const referringUser = referredBy
                                ? window.admin.referrers[referredBy]
                                : null;
                              const {
                                referral_id: referringId,
                                username: referringUsername
                              } = referringUser;
                              return !referringUser ? null : (
                                <a
                                  href={`/carnage-referral-legacy/?u=${referringId}`}
                                  className="text-white"
                                >
                                  {referringUsername}
                                </a>
                              );
                            case 'links':
                              return [
                                <a
                                  key={`${id}-${login}-${key}-link-wp`}
                                  className="btn btn-sm btn-white px-1 py-0 mr-1"
                                  href={`/wp-admin/user-edit.php?user_id=${ID}`}
                                  rel="noopener noreferrer"
                                  target="_blank"
                                >
                                  Edit
                                </a>
                              ];
                            default:
                              return null;
                          }
                        })()}
                      </td>
                    ))}
                  </tr>
                )
              )}
          </tbody>
          <tfoot>
            <tr>
              <td colSpan={Object.keys(columns).length}>
                <Pagination
                  id={`${id}-pagination`}
                  count={users.length}
                  limit={limit}
                  skip={skip}
                  setSkip={this.setSkip}
                />
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    );
  }
}

const CarnageHeader = () => [
  <header className="wp-block-carney-section bg--gradient bg--gradient--pink" key="header">
    <div className="container py-sm text-center">
      <img src={carnageLogo} alt="Carnage" width={600} height={74} style={{ maxWidth: '80%' }} />
    </div>
  </header>,
  !window.admin ? null : (
    <section className="wp-block-carney-section bg-dark text-white referral-admin" key="admin">
      <div className="container py-sm">
        <h1 className="h5 text-center">Top Secret Admin Zone</h1>
        <p className="lead text-center mt-5">
          There have been{' '}
          <strong>{Object.keys(window.admin.referred_by).length} total referrals</strong> from{' '}
          <strong>{window.admin.top_referrers.length} users</strong>.
        </p>
        <div className="row">
          <div className="col-lg-5 mt-4">
            <h2 className="h6 mb-3">Leaderboard</h2>
            <UserTable
              id="top"
              users={window.admin.top_referrers}
              columns={{ rank: 'Rank', user: 'User', links: '', count: 'Count' }}
            />
          </div>
          <div className="col-lg-7 mt-4">
            <h2 className="h6 mb-3">Latest Referrals</h2>
            <UserTable
              id="latest"
              users={window.admin.latest_referrals}
              columns={{ user: 'User', links: '', referred_by: 'Referred By', registered: 'Date' }}
            />
          </div>
        </div>
      </div>
    </section>
  )
];

class UserPage extends PureComponent {
  constructor(props) {
    super(props);

    this.copyLink = this.copyLink.bind(this);

    this.state = {
      linkCopied: false
    };
  }

  // eslint-disable-next-line
  copyLink(e) {
    e.preventDefault();

    copy(e.target.value);

    setTimeout(() => {
      this.setState({ linkCopied: false });
    }, 2000);

    this.setState({ linkCopied: true });
  }

  render() {
    const { linkCopied } = this.state;
    const { user, rewards } = window;
    const { protocol, host } = document.location;
    const {
      ID,
      username,
      referrals,
      referral_id: referralId = '',
      referred_by: referredBy,
      user_email: email
    } = user;
    const referralLink = `${protocol}//${host}/carnage-referral/?r=${referralId}`;
    const referralCount = referrals.length;
    const nextReward = rewards.reduce((prev, { count, title }) =>
      (count > referralCount && prev.count <= referralCount ? { count, title } : prev)
    );
    const referralsToGo = nextReward.count - referralCount;

    return (
      <div className="app-content-inner">
        <CarnageHeader />

        <section className="wp-block-carney-section bg-white">
          <div className="container py-sm text-center">
            <h1 className="h5 mb-4">Welcome to the Daily Carnage referral program!</h1>
            <p className="lead">
              Earn swag by referring your friends to the best dang marketing newsletter on the
              planet.
            </p>
            {!window.admin ? null : (
              <div className="row mt-4 text-center justify-content-center">
                <div className="w-100 w-lg-auto mt-3 px-4">
                  <strong className="mr-2">Username:</strong> {username}
                </div>
                <div className="w-100 w-lg-auto mt-3 px-4">
                  <strong className="mr-2">Email:</strong>
                  <a className="text-dark" href={`mailto:${email}`}>
                    {email}
                  </a>
                </div>
                {!Object.keys(referredBy).length ? null : (
                  <div className="w-100 w-lg-auto mt-3 px-4">
                    <strong className="mr-2">Referred by:</strong>
                    <a
                      className="text-dark"
                      href={`/carnage-referral-legacy/?u=${referredBy.referral_id}`}
                    >
                      {referredBy.username}
                    </a>
                  </div>
                )}
                <div className="w-100 w-lg-auto mt-3 px-4">
                  <a
                    className="btn btn-sm btn-dark px-1 py-0"
                    href={`/wp-admin/user-edit.php?user_id=${ID}`}
                    rel="noopener noreferrer"
                    target="_blank"
                  >
                    Edit
                  </a>
                </div>
              </div>
            )}
          </div>
        </section>

        <section className="wp-block-carney-section referral-progress bg-gray">
          <div className="container py-5 text-center">
            <h2 className="h6 mb-4">Your progress</h2>
            {referralCount ? (
              <p>
                <strong>
                  You&#39;ve referred {referralCount} subscriber{referralCount !== 1 ? 's' : ''} to
                  the Daily Carnage!
                </strong>
              </p>
            ) : null}
            <p>
              {referralsToGo} referral{referralsToGo !== 1 ? 's' : ''} to go to earn
              {nextReward.title.replace(/^\w/, c => c.toLowerCase())}
            </p>
            <div
              className="progress my-5 mx-auto bg-dark"
              style={{
                maxWidth: 600,
                height: '2.5rem',
                borderRadius: '1.25rem'
              }}
            >
              <div
                className={
                  referralCount
                    ? 'progress-bar progress-bar-striped bg-warning'
                    : 'progress-bar progress-bar-striped bg-gray-md text-dark'
                }
                style={{
                  // eslint-disable-next-line no-mixed-operators
                  width: `${referralCount ? (referralCount / nextReward.count) * 100 : 100}%`
                }}
              >
                {referralCount ? null : <strong>No referrals yet! Get crackin&#39;</strong>}
              </div>
            </div>

            {!referralCount ? null : (
              <div className="mt-5 mb-3 row">
                <div className="col-md-10 offset-md-1">
                  <h2 className="h6 mb-4">Your referrals</h2>
                  <ul className="list-inline mb-0">
                    {referrals.map(
                      (
                        { username: refUsername, referral_count: count, referral_id: ref },
                        index
                      ) => {
                        const displayName = count
                          ? [
                              refUsername,
                            <span className="referred-user__count" key={`${refUsername}-count`}>
                              {count}
                            </span>
                            ]
                          : refUsername;
                        return (
                          <li
                            className="list-inline-item mb-2"
                            // eslint-disable-next-line react/no-array-index-key
                            key={`referral-${referralId}-${index}`}
                          >
                            {ref ? (
                              <a href={`/carnage-referral-legacy/?u=${ref}`} className="referred-user">
                                {displayName}
                              </a>
                            ) : (
                              <span className="referred-user">{displayName}</span>
                            )}
                          </li>
                        );
                      }
                    )}
                  </ul>
                </div>
              </div>
            )}

            <div className="row">
              <div className="col-12 col-lg-10 offset-lg-1">
                <div className="row">
                  <div className="col-12 col-lg-8 mt-4 mt-lg-3 text-center text-lg-left">
                    <label htmlFor="referral-code" className="w-100 mb-3">
                      Your magic link
                      <span className={`copy-label ${linkCopied ? 'copied' : ''}`}>
                        <span className="sr-only">Copy URL to clipboard</span>
                        <span className="copy-label-alert">
                          <span className="sr-only" aria-live="polite">
                            Link copied!
                          </span>
                        </span>
                      </span>
                    </label>
                    <input
                      type="text"
                      className="form-control bg-white"
                      id="referral-code"
                      value={referralLink}
                      onClick={this.copyLink}
                      readOnly
                    />
                  </div>
                  <div className="col-12 col-lg-4 mt-4 mt-lg-3 text-center text-lg-right">
                    <p className="mb-3">Share it</p>
                    <ul className="referral-share justify-content-lg-end">
                      <li>
                        <a
                          href={`https://twitter.com/intent/tweet?url=${referralLink}&text=Check out the Daily Carnage, the best dang marketing newsletter on the planet!`}
                        >
                          <TwitterIcon />
                        </a>
                      </li>
                      <li>
                        <a href={`https://www.facebook.com/sharer/sharer.php?u=${referralLink}`}>
                          <FacebookIcon />
                        </a>
                      </li>
                      <li>
                        <a
                          href={`https://www.linkedin.com/shareArticle?url=${referralLink}&mini=true&title=The Daily Carnage&summary=The best dang marketing newsletter on the planet!`}
                        >
                          <LinkedInIcon />
                        </a>
                      </li>
                      <li className="pr-lg-0">
                        <a
                          href={encodeURI(
                            `mailto:?subject=Check out the Daily Carnage!&body=Check out the Daily Carnage, the best dang marketing newsletter on the planet!\n\n${referralLink}`
                          )}
                        >
                          <EmailIcon />
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="wp-block-carney-section referral-rewards bg-white">
          <div className="container py-sm text-center">
            <h2 className="h6 mb-5">The rewards</h2>
            <div className="row">
              {rewards.map(({ count, title }) => (
                <div className="col-12 col-lg pb-sm pb-lg-sm" key={title}>
                  <div
                    className={`referral-rewards__reward ${
                      count <= referralCount ? 'is-active' : ''
                    }`}
                  >
                    <p className="h3">
                      {count} <small className="align-items-center">Referrals</small>
                    </p>
                    <p className="mt-3">
                      {title.replace(/^A\s+/, '').replace(/^./, c => c.toUpperCase())}
                    </p>
                    <CheckIcon />
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      </div>
    );
  }
}

class ReferralPage extends PureComponent {
  constructor(props) {
    super(props);

    this.processForm = this.processForm.bind(this);
    this.form = React.createRef();
    this.state = {
      isSubmitting: false
    };
  }

  processForm(event) {
    event.preventDefault();
    this.setState({
      isSubmitting: true
    });

    const { method, action: url } = event.target;
    const body = JSON.stringify(serialize(event.target, { hash: true }));

    window
      .fetch(url, {
        method,
        body,
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(res => res.json())
      .then(({ success, error }) => {
        if (success) {
          this.form.current.reset();
        }

        this.setState({
          status: success ? 'success' : 'error',
          statusText: success ? 'Please click the link in your email to confirm.' : error
        });
      })
      .finally(() => {
        this.setState({ isSubmitting: false });
      });
  }

  render() {
    const { ref } = window;
    const { isSubmitting, status, statusText } = this.state;
    return (
      <div className="app-content-inner">
        <CarnageHeader />

        <section className="wp-block-carney-section bg-white">
          <div className="container py-md text-center">
            <h1 className="h4 mb-4">Great news!</h1>
            <p className="lead">
              You&#39;ve been invited to subscribe to the Daily Carnage, the best dang marketing
              newsletter on the planet.
            </p>
            <p>
              The Daily Carnage is your handpicked list of the best marketing content delivered to
              your inbox each day.
            </p>
            <form
              className="sign-up-form mx-auto mt-5"
              action="/wp-json/carnage/v1/subscribe"
              method="post"
              onSubmit={this.processForm}
              ref={this.form}
            >
              <input
                className="sign-up-form__input"
                type="email"
                name="email"
                placeholder="Enter your email"
                required
              />
              <input type="hidden" value={ref} name="ref_by" />
              <div style={{ position: 'absolute', left: '-5000px' }} aria-hidden="true">
                <input type="text" name="username" tabIndex="-1" />
              </div>
              <button
                className="sign-up-form__button btn btn--gradient btn--gradient--pink"
                type="submit"
              >
                Sign up
              </button>
              <img
                src={loading}
                width=""
                height=""
                alt="Loading"
                className={isSubmitting ? 'spinner is-active' : 'spinner'}
              />
            </form>
            {!isSubmitting && statusText ? (
              <div className={`sign-up-form__status sign-up-form__status--${status}`}>
                <strong>{status === 'success' ? 'Success! ' : 'Error: '}</strong>
                {statusText}
              </div>
            ) : null}
          </div>
        </section>
      </div>
    );
  }
}

function GenericPage() {
  const { rewards } = window;

  return (
    <div className="app-content-inner">
      <CarnageHeader />

      <section className="wp-block-carney-section bg-white">
        <div className="container py-sm text-center">
          <h1 className="h5 mb-4">Welcome to the Daily Carnage referral program!</h1>
          <p className="lead">
            Earn swag by referring your friends to the best dang marketing newsletter on the planet.
          </p>
        </div>
      </section>

      <section className="wp-block-carney-section referral-rewards bg-white">
        <div className="container py-sm text-center">
          <h2 className="h6 mb-5">The rewards</h2>
          <div className="row">
            {rewards.map(({ count, title }) => (
              <div className="col-12 col-lg pb-sm pb-lg-sm" key={title}>
                <div className="referral-rewards__reward">
                  <p className="h3">
                    {count} <small className="align-items-center">Referrals</small>
                  </p>
                  <p className="mt-3">
                    {title.replace(/^A\s+/, '').replace(/^./, c => c.toUpperCase())}
                  </p>
                  <CheckIcon />
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}

const App = () => {
  const { user, ref } = window;
  if (user) {
    return <UserPage />;
  }
  return ref ? <ReferralPage /> : <GenericPage />;
};

render(<App />, document.getElementById('app-content'));
