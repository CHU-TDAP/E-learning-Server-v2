# A sample Guardfile
# More info at https://github.com/guard/guard#readme

# Add files and commands to this file, like the example:
#   watch(%r{file/path}) { `command(s)` }
#

# Mac用的通知中心
notification :growl
# Linux用的通知中心
notification :libnotify

guard :shell do
  watch(%r{htdocs/.+\.(php)}) do
    system 'phpdoc', '-d', './htdocs/lib', '-t', './docs/'
  end
end

# LiveReload
guard 'livereload' do
  watch(%r{htdocs/.+\.(php|css|js|html)})
end

# PHPUnit
guard :phpunit2, :all_on_start => false, :tests_path => 'tests/', :cli => '--colors -c phpunit.xml' do
  # Run any test in app/tests upon save.
  watch(%r{^tests/.+Test\.php$})

  # When a file is edited, try to run its associated test.
  watch(%r{^htdocs/lib/(.+)\.php}) { |m| "tests/#{m[1]}Test.php" }
end