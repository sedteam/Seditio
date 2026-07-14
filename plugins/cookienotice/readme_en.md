# Installation and Configuration Manual for `cookienotice` Plugin

The plugin displays a cookie usage notice and statistic services consent bar to the website visitors with "Accept all" and "Decline" buttons.

## Step 1. Installing the plugin
1. Go to the Seditio **Administration Panel**.
2. Click on **Tools / Plugins**.
3. Find **Cookie Notice** in the list and click **Install**.

## Step 2. Configuring the plugin
1. Once installed, click on the **Configuration** button for the **Cookie Notice** plugin.
2. Fill in the options:
   - **Cookie notice text**: You can leave this field empty to use the default text from the language file, or write your own. The field supports `{STAT_URL}` and `{POLICY_URL}` placeholders, which will be automatically replaced with the links configured below.
   - **Link to statistic services page**: Enter the URL for cookie info page (e.g. `/sborstat`).
   - **Link to Privacy Policy page**: Enter the URL for the privacy policy page (e.g. `/policy`).

## Step 3. Adding the tag to the template
To output the notice, you need to add the `{FOOTER_COOKIENOTICE}` tag to your footer template.

1. Open the footer template file of your active skin:
   `skins/<your_skin_name>/footer.tpl`
2. Add the `{FOOTER_COOKIENOTICE}` tag before the closing `</body>` tag. It is recommended to place it right before the loading of main JS files. Example:
   ```html
   {FOOTER_COOKIENOTICE}
   {FOOTER_JAVASCRIPT}
   ```
3. Save the changes and clear the site cache in the Administration Panel.
