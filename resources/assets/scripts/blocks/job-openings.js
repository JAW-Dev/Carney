import React from 'react';
import { render } from 'react-dom';
import JobTable from '../components/job-table';

function initJobOpenings(el) {
  if (el) {
    const { id, className } = el;
    const { [id]: block } = window.jobOpeningsBlocks || {};
    const { jobOpenings } = block || {};
    if (jobOpenings.length) {
      render(
        <JobTable tableId={id} tableClassName={className} jobOpenings={jobOpenings} limit={10} />,
        el
      );
    }
  }
}

if (typeof window.acf !== 'undefined') {
  window.acf.addAction('render_block_preview/type=job-openings', initJobOpenings);
} else {
  $('.job-openings').each((index, el) => initJobOpenings(el));
}
