import toastr from 'toastr';

const STYLESHEET = {
  0: `
  body, body * {
    transition: color 1s, background-color 1s, border-color 1s;
    -moz-transition: color 1s, background-color 1s, border-color 1s;
    -webkit-transition: color 1s, background-color 1s, border-color 1s;
    -o-transition: color 1s, background-color 1s, border-color 1s;
  }`,

  1000: `
  body {
    background-color: #000;
  }`,

  2000: `
  body * {
    background-color: rgba(0, 0, 0, 0.2) !important;
    color: #fff !important;
    border-color: #fff !important;
  }`
}

function addStyle(css) {
  let style = document.createElement('style');
  style.type = 'text/css';

  if (style.styleSheet) { // IE
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }

  document.getElementsByTagName('head')[0].appendChild(style);
}

export function init() {
  setTimeout(function() {
    addStyle(STYLESHEET[0]);
    setTimeout(function() {
      addStyle(STYLESHEET[1000]);
      setTimeout(function() {
        addStyle(STYLESHEET[2000]);
        setTimeout(function() {
          toastr.info('Happy Halloween!');
        }, 3000);
      }, 2000);
    }, 1000);
  }, 0);
}
