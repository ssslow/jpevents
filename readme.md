# JPEvents Plugin

A powerful event management plugin that allows WordPress users to add, manage, and display events. Enhance your website's functionality with robust event scheduling features.

## Description

JPEvents simplifies event management on your WordPress site. It provides tools to create events, manage attendees, and display upcoming events using customizable templates. Ideal for businesses, community groups, and anyone needing a reliable event management solution.

## Installation

### Install and Activate the Plugin

- **Download the Plugin**:
  Visit [My Plugin Page](https://mypluginpage.com/download) to download the latest version of the JPEvents plugin.

- **Upload and Activate**:
  - Go to your WordPress dashboard, navigate to `Plugins` > `Add New` > `Upload Plugin`.
  - Choose the downloaded plugin file and click `Install Now`.
  - After the installation is complete, click `Activate Plugin`.

### Install and Activate the Theme

- **Download the Recommended Theme**:
  For an optimal experience, download the Go theme directly from the [WordPress Theme Directory](https://wordpress.org/themes/go/).

- **Upload and Activate**:
  - In your WordPress dashboard, go to `Appearance` > `Themes` > `Add New` > `Upload Theme`.
  - Choose the downloaded theme file and click `Install Now`.
  - Activate the theme by clicking `Activate`.

### Create an Events Listing Page

- **Create a New Page**:
  - Navigate to `Pages` > `Add New` in your WordPress dashboard.
  - Title your page (e.g., 'Events').
  - In the page attributes section, select 'Events Listing Page' from the template drop-down menu.

- **Publish the Page**:
  Click `Publish` to make your events listing page live.

### Set Up the Homepage

- **Assign the Events Page as the Homepage**:
  - Go to `Settings` > `Reading` in your WordPress dashboard.
  - Under `Your homepage displays`, select `A static page`.
  - For `Homepage`, select the events page you created from the drop-down menu.
  - Click `Save Changes` to set your new events listing page as the homepage.

## Features

- **Custom Post Type for Events**: Easily create and manage events.
- **Event Categories**: Organize events into categories for easier management.
- **Customizable Templates**: Adjust the appearance of events on your site.

## Template Overrides

The plugin provides default templates for displaying events. You can override these templates by copying them from the plugin's `templates` folder to your theme:

### Overriding Templates

1. Navigate to `wp-content/plugins/jpevents/templates`.
2. Choose the template you want to override.
3. Copy it to the corresponding location in your theme. Ensure you maintain the same file structure.

For example, to override the single event template:
- Copy from: `wp-content/plugins/jpevents/templates/single/single-jpevents_event.php`
- To: `wp-content/themes/your-theme/single-jpevents_event.php`

This allows you to customize the layout and styling of the event listings and details pages without altering the plugin files directly.
