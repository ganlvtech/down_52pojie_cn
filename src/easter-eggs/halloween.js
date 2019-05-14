import toastr from 'toastr';

const STYLESHEET = {
  transition: `
  body, body * {
    transition: color 2s, background-color 2s, border-color 2s;
    -moz-transition: color 2s, background-color 2s, border-color 2s;
    -webkit-transition: color 2s, background-color 2s, border-color 2s;
    -o-transition: color 2s, background-color 2s, border-color 2s;
  }
  `,
  bodyBlack: `
  body {
    background-color: #000;
  }`,
  allBlack: `
  body * {
    background-color: transparent !important;
    color: #fff !important;
    border-color: #fff !important;
  }
  .breadcrumb {
    box-shadow: inset 0 0 0px 1px;
  }`
};

function addStyle(css) {
  let style = document.createElement('style');
  style.appendChild(document.createTextNode(css));
  document.getElementsByTagName('head')[0].appendChild(style);
  return style;
}

function enableTransition() {
  addStyle(STYLESHEET.transition);
}

let bodyBlackStyleElement;

function setBodyBlack() {
  bodyBlackStyleElement = addStyle(STYLESHEET.bodyBlack);
}

function setAllBlack() {
  addStyle(STYLESHEET.allBlack);
}

function toast() {
  toastr.info('Happy Halloween!');
}

export function init() {
  enableTransition();
  setTimeout(setBodyBlack, 2000);
  setTimeout(setAllBlack, 4000);
  setTimeout(toast, 5000);
}
