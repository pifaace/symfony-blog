after 'deploy:updated', 'docker:build'
after 'deploy:updated', 'template:compose'
after 'deploy:updated', 'template:env'

after 'deploy:updated', 'composer:install'

after 'deploy:updated', 'node:install'
after 'deploy:updated', 'node:build'

after 'deploy:updated', 'docker:stop'

after 'deploy:updated', 'doctrine:migrate'

after 'deploy:published', 'docker:up'
