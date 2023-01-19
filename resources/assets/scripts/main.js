// import external dependencies
import 'jquery';

// Import everything from autoload
import './autoload/_bootstrap';

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import termAd from './routes/past-ads';
import capabilities from './routes/capabilities';
import work from './routes/work';
import dicksSportingGoods from './routes/dicks-sporting-goods';
import wolfFurniture from './routes/wolf-furniture';
import carnegieMuseumOfArt from './routes/carnegie-museum-of-art';
import senecaCreek from './routes/seneca-creek';
import aboutUs from './routes/about-us';
import prospectLanding from './routes/prospect-landing';
import hireUs from './routes/hire-us';
import duolingo from './routes/duolingo';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // Ads from the Past
  termAd,
  // Capabilities
  capabilities,
  // Work
  work,
  postTypeArchiveClient: work,
  // Client - Dick's Sporting Goods
  dicksSportingGoods,
  // Client - Carnegie Museum of Art
  carnegieMuseumOfArt,
  // Client - Wolf Furniture
  wolfFurniture,
  // Client - Seneca Creek
  senecaCreek,
  // About Us
  aboutUs,
  // Hire Us
  hireUs,
  duolingo,
  // Prospect Landing Pages
  artVan: prospectLanding,
  argosy: prospectLanding
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());
