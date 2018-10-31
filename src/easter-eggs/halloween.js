const css = `
body {
    background-color: rgba(0, 0, 0, 1);
}
body * {
    background-color: rgba(0,0,0, 0.2)!important;
    color: #fff !important;
    border-color: #fff !important;
}`;

var style = document.createElement('style');
style.type = 'text/css';

if (style.styleSheet) { // IE
    style.styleSheet.cssText = css;
} else {
    style.appendChild(document.createTextNode(css));
}

document.getElementsByTagName('head')[0].appendChild(style);
