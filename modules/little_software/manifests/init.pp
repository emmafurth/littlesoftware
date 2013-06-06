# == Class: little_software
#
# Full description of class little_software here.
#
# === Parameters
#
# Document parameters here.
#
# [*sample_parameter*]
#   Explanation of what this parameter affects and what it defaults to.
#   e.g. "Specify one or more upstream ntp servers as an array."
#
# === Variables
#
# Here you should define a list of variables that this module would require.
#
# [*sample_variable*]
#   Explanation of how this variable affects the funtion of this class and if
#   it has a default. e.g. "The parameter enc_ntp_servers must be set by the
#   External Node Classifier as a comma separated list of hostnames." (Note,
#   global variables should not be used in preference to class parameters as
#   of Puppet 2.6.)
#
# === Examples
#
#  class { little_software:
#    servers => [ 'pool.ntp.org', 'ntp.local.company.com' ],
#  }
#
# === Authors
#
# Author Name <author@domain.com>
#
# === Copyright
#
# Copyright 2013 Your name here, unless otherwise noted.
#
class little_software {
  package { 'php5-gd': 
    ensure => installed, 
    notify => Service['httpd'],
  }

  package { 'php5-mysql': 
    ensure => installed, 
    notify => Service['httpd'],
  }

  file { '/opt/little-software/inc/config.php':
    ensure => file,
    source => 'puppet:///modules/little_software/config.php',
    mode   => 0755,
  }

  mysql::db { 'littlesoftware':
    user     => 'little',
    password => 'software',
    sql      => '/opt/little-software/install/sql/data.sql',
  }
}
