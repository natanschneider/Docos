FROM node:22-alpine AS builder

RUN apk add --no-cache \
    php \
    php-cli \
    php-fpm \
    php-json \
    php-mbstring \
    php-xml \
    php-curl

WORKDIR /app

COPY package*.json ./
RUN npm ci --frozen-lockfile

COPY . .
RUN npm run build:ssr

FROM node:22-alpine AS production

WORKDIR /app

COPY --from=builder /app/bootstrap/ssr ./bootstrap/ssr

EXPOSE 13714

CMD ["node", "bootstrap/ssr/ssr.js"]
