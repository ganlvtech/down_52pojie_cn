import './styles/index.scss';
import Down52PojieCn from './Down52PojieCn';

require('popper.js');
require('bootstrap');

window.Down52PojieCn = Down52PojieCn;

(function() {
  const moment = require('moment');
  let date = moment().format("MMDD")
  if (date === '1030' || date === '1031' || date === '1101') {
    const halloween = require('./easter-eggs/halloween');
    halloween.init();
  }
})();
