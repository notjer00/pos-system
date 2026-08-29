# Nix overlay to build PHP with libmysqlclient instead of mysqlnd
self: super: {
  php84 = super.php84.override {
    withMysqlnd = false;
    withLibmysqlclient = true;
  };
}