# down.52pojie.cn

Demo: <https://ganlvtech.github.io/down_52pojie_cn/>

## ngx-fancyindex

Install [ngx-fancyindex](https://github.com/aperezdc/ngx-fancyindex) from distribution or source code.

Download the source of this theme and copy `ngx-fancyindex` to your site's root directory.

Edit the site config like the following example.

```nginx
location / {
    fancyindex         on;
    fancyindex_header  "/ngx-fancyindex/header.html";
    fancyindex_footer  "/ngx-fancyindex/footer.html";
    fancyindex_ignore  "ngx-fancyindex";
}
```

The `fancyindex_ignore` means `/ngx-fancyindex/` won't be listed on your homepage, but it still can be visited.

You can use the following route to prevent user from visiting the `header.html` and `footer.html` template.

```nginx
location /ngx-fancyindex/ {
    deny  all;
}
location /ngx-fancyindex/fancyindex.css {
    allow  all;
}
```

## LICENSE

The MIT License (MIT)

Copyright (c) 2018 Ganlv

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
