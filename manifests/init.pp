node default {
  class { 'little_software': }

  class { 'mysql::server': 
     config_hash => { 'root_password' => 'foo' }
  } -> 
  class { 'apache': }
  class { 'apache::mod::php': }

  apache::vhost { 'www':
      priority        => '10',
      vhost_name      => '192.168.33.10',
      port            => '80',
      docroot         => '/opt/little-software',
  }
}
