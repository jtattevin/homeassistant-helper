ARG ALPINE_VERSION=latest
ARG ALPINE_PACKAGES

FROM php:${ALPINE_VERSION}
RUN apk add ${ALPINE_PACKAGES} composer
COPY . /app
WORKDIR /app/public
CMD [ "php", "-S", "0.0.0.0:8081" ]
