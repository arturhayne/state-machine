## Requirements
  - Docker
 
 ## How to Run 
 1. docker-compose up -d
 2. docker-compose exec web bash
 3. composer install (inside container)
 4. php index (inside container) 
   - Using this command 
        - Default source file is events.json
        - Default target file is result.json
   - You can specify a source and target file using the path like:
        - php index.php source.json target.json 
   - Outside Container: docker run --rm -it --volume %cd%:/app php bash -c "cd app/; php index.php"