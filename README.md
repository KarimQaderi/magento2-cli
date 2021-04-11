# Install global
composer global remove m2/cli

composer global require m2/cli:dev-master --no-cache

add to file `.bashrc`

`export PATH="$HOME/.composer/vendor/bin:$PATH"`

`source .bashrc`

# Use
only `m2`


# Install project
composer remove m2/cli --no-update
composer require m2/cli:dev-master --no-cache

ln -s vendor/m2/cli/bin/m2 m2

# Use
only `./m2`


