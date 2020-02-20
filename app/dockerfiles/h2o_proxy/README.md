# H2O server as proxy

The following files allow for [H2O HTTP server](https://h2o.examp1e.net/) to be used as a proxy. It provides HTTP2 and SSL.
Swoole & Guzaba2 also support HTTP2 and SSL but Swoole has worse performance when used with SSL versus H2O (SSL) as proxy + Swoole (non SSL).