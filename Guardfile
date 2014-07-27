# A sample Guardfile
# More info at https://github.com/guard/guard#readme

group :development do
  gem 'guard'
  gem 'guard-livereload', require: false
  gem 'guard-shell'
end

# Add files and commands to this file, like the example:
#   watch(%r{file/path}) { `command(s)` }
#
guard :shell do
  watch(%r{htdocs/.+\.(php)}) do
    system 'phpdoc', '-d', './htdocs/lib', '-t', './docs/'
  end
end

# LiveReload
guard 'livereload' do
  watch(%r{htdocs/.+\.(php|css|js|html)})
end
