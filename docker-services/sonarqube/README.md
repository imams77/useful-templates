# SonarQube Docker Setup

This Docker Compose configuration sets up SonarQube with a PostgreSQL database for code quality and security analysis.

## Services

- **SonarQube**: Code quality and security analysis platform (LTS Community Edition)
- **PostgreSQL**: Database backend for SonarQube

## Prerequisites

- Docker and Docker Compose installed
- Minimum 4GB of RAM allocated to Docker
- For Linux hosts, increase virtual memory limits (see below)

### Linux Host Requirements

SonarQube uses Elasticsearch internally, which requires increased virtual memory limits. Run these commands on your host:

```bash
# Temporary (resets on reboot)
sudo sysctl -w vm.max_map_count=524288
sudo sysctl -w fs.file-max=131072

# Permanent (add to /etc/sysctl.conf)
echo "vm.max_map_count=524288" | sudo tee -a /etc/sysctl.conf
echo "fs.file-max=131072" | sudo tee -a /etc/sysctl.conf
sudo sysctl -p
```

## Quick Start

1. Clone or navigate to this directory

2. (Optional) Copy and customize environment variables:
   ```bash
   cp env.template .env
   # Edit .env to customize settings
   ```

3. Start the services:
   ```bash
   docker-compose up -d
   ```

4. Access SonarQube at: http://localhost:9000

5. Default credentials:
   - **Username**: `admin`
   - **Password**: `admin`
   
   You will be prompted to change the password on first login.

## Configuration

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `SONARQUBE_IMAGE` | `sonarqube:lts-community` | SonarQube Docker image |
| `SONARQUBE_PORT` | `9000` | Host port for SonarQube |
| `POSTGRES_USER` | `sonar` | PostgreSQL username |
| `POSTGRES_PASSWORD` | `sonar` | PostgreSQL password |
| `POSTGRES_DB` | `sonar` | PostgreSQL database name |

### Volumes

- `sonarqube_data`: SonarQube data files
- `sonarqube_extensions`: Plugins and extensions
- `sonarqube_logs`: Log files
- `postgresql_data`: PostgreSQL database files

## Usage

### Start Services
```bash
docker-compose up -d
```

### Stop Services
```bash
docker-compose down
```

### View Logs
```bash
docker-compose logs -f sonarqube
docker-compose logs -f db
```

### Reset Everything (including data)
```bash
docker-compose down -v
```

## Installing Plugins

1. Download the plugin JAR file
2. Copy it to the extensions volume:
   ```bash
   docker cp plugin.jar sonarqube:/opt/sonarqube/extensions/plugins/
   ```
3. Restart SonarQube:
   ```bash
   docker-compose restart sonarqube
   ```

## Analyzing a Project

### Using SonarScanner (Docker)

```bash
docker run --rm \
  --network sonarqube_sonarnet \
  -v "$(pwd):/usr/src" \
  sonarsource/sonar-scanner-cli \
  -Dsonar.projectKey=my-project \
  -Dsonar.sources=/usr/src \
  -Dsonar.host.url=http://sonarqube:9000 \
  -Dsonar.login=your-token
```

### Using Maven

```bash
mvn sonar:sonar \
  -Dsonar.projectKey=my-project \
  -Dsonar.host.url=http://localhost:9000 \
  -Dsonar.login=your-token
```

## Production Recommendations

1. **Change default credentials** immediately after first login
2. **Use strong passwords** for PostgreSQL (update in `.env` and restart)
3. **Enable HTTPS** using a reverse proxy (nginx, traefik, etc.)
4. **Backup volumes** regularly, especially `postgresql_data`
5. **Monitor resources** - SonarQube can be memory-intensive

## Troubleshooting

### SonarQube not starting
- Check logs: `docker-compose logs sonarqube`
- Verify vm.max_map_count is set correctly
- Ensure enough memory is available

### Database connection issues
- Ensure PostgreSQL is running: `docker-compose ps`
- Check PostgreSQL logs: `docker-compose logs db`

### Out of memory
- Increase Docker memory allocation
- Adjust JVM options in environment variables

## Resources

- [SonarQube Documentation](https://docs.sonarqube.org/)
- [SonarQube Docker Hub](https://hub.docker.com/_/sonarqube)
- [SonarScanner Documentation](https://docs.sonarqube.org/latest/analysis/scan/sonarscanner/)