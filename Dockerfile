FROM php:8.2-apache

# Enable Apache mod_rewrite (optional but useful)
RUN a2enmod rewrite

# Copy your PHP site into the Apache web root
COPY . /var/www/html/

# Create uploads directory and make it writable by Apache
RUN mkdir -p /var/www/html/uploads/photos \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

# Increase PHP upload limits
RUN echo "upload_max_filesize = 10M\npost_max_size = 10M" > /usr/local/etc/php/conf.d/uploads.ini

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]