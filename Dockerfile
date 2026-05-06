FROM php:8.2-apache

# Enable Apache mod_rewrite (optional but useful)
RUN a2enmod rewrite

# Copy your PHP site into the Apache web root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
