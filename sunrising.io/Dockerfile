# Use the official Node.js 11 image to build the assets.
FROM node:11 as builder

# Create and change to the app directory.
WORKDIR /usr/src/app

# Copy application dependency manifests to the container image.
# A wildcard is used to ensure both package.json AND package-lock.json are copied.
# Copying this separately prevents re-running npm install on every code change.
COPY package.json package*.json ./

# Install production dependencies.
RUN npm install --only=production

# Copy local code to the container image.
COPY . .
RUN npm run build

# Use the official apache image to serve the static files
FROM httpd:2.4.39-alpine
# Cloud Run needs to define the port as an environment variable
ENV PORT 80
ENTRYPOINT []
COPY --from=builder /usr/src/app/dist/ ./htdocs/
EXPOSE $PORT

CMD sed -i "s/80/$PORT/g" $HTTPD_PREFIX/conf/httpd.conf && httpd-foreground
