Compile scss files with sass commands. Note that we are adding
path to foundation vendor directory.
Run this command from your theme root directory :

  sass --watch scss:css --load-path foundation/bower_components/foundation/scss

foundation vendor directory has been installed with following command :

  foundation new foundation --libsass

So he can be updated easily with following command

  foundation update

(@see foundation frameworks doc on how to install foundation with sass for more informations,
you'll need foundation ruby-gem, bower, nodejs etc...)

