import toastr from 'toastr';

const STYLESHEET = {
  0: `
  body, body * {
    transition: color 2s, background-color 2s, border-color 2s;
    -moz-transition: color 2s, background-color 2s, border-color 2s;
    -webkit-transition: color 2s, background-color 2s, border-color 2s;
    -o-transition: color 2s, background-color 2s, border-color 2s;
  }`,

  1000: `
  body {
    background-color: #000;
  }`,

  2000: `
  body * {
    background-color: transparent !important;
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

  return style;
}

export function init() {
  setTimeout(function() {
    addStyle(STYLESHEET[0]);

    setTimeout(function() {
      let style = addStyle(STYLESHEET[1000]);

      let loop = function() {
        style.remove();

        setTimeout(function() {
          style = addStyle(STYLESHEET[1000]);

          setTimeout(loop, 10000);
        }, 2000);
      };
      setTimeout(loop, 10000);

      setTimeout(function() {
        addStyle(STYLESHEET[2000]);
        setTimeout(function() {
          toastr.info('Happy Halloween!');
        }, 1000);
      }, 2000);
    }, 2000);
  }, 0);
}
