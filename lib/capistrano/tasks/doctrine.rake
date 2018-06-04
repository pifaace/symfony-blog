namespace :doctrine do
  desc 'Running migrations'
  task :migrate do
    on roles(:app) do
      within release_path do
        execute 'docker-compose' , :run, '--rm', 'symfony-blog', :php, 'bin/console', 'doctrine:migrations:migrate'
      end
    end
  end
end
