namespace :template do
  desc 'Copying the docker-compose file'
  task :compose do
    on roles(:app) do
      within release_path do
        execute :cp, fetch(:docker_compose), 'docker-compose.yml'
      end
    end
  end
end
