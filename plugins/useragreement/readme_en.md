# Installation Guide for `useragreement` Plugin

The plugin displays a checkbox on the registration form to accept the User Agreement, Privacy Policy, and Consent to Personal Data Processing.

## Step 1. Installing the Plugin
1. Go to the Seditio **Administration Panel**.
2. Navigate to **Tools / Plugins**.
3. Locate the **User Agreement** plugin and click **Install**.

## Step 2. Configuring the Plugin
1. After installation, click the **Configuration** button for the **User Agreement** plugin (or navigate to *Configuration* -> *User Agreement*).
2. Enter the URLs for the required documents:
   - **User Agreement Document URL**
   - **Privacy Policy Document URL**
   - **Personal Data Processing Consent Document URL**
   
   *You can provide all 3 URLs, or any combination of them. The agreement text and error messages will be generated automatically based on your active URLs.*

## Step 3. Template Integration
To display the checkbox on the registration form, you need to add the `{USERS_REGISTER_AGREEMENT}` tag to your skin's registration template.

1. Open the registration template file in your active skin:
   `skins/<your_skin_name>/users.register.tpl`
   *(If this file is missing in your skin directory, copy it from `modules/users/tpl/users.register.tpl` to your skin directory).*

2. Find the place just before the submit button (typically near `<input type="submit" ...>`).

3. Add the following code block:
   ```html
   <li class="form-row">
       <div class="form-field-100">
           {USERS_REGISTER_AGREEMENT}
       </div>
   </li>
   ```
4. Save the template file and clear the site cache in the administration panel.

## Step 4. Styling (optional)
To ensure the links to the documents are underlined and visually distinct, you can add the following CSS rules to your skin's stylesheet (e.g., `index.css` or `custom.css`):

```css
.useragreement-label a {
	text-decoration: underline;
}
.useragreement-label a:hover {
	text-decoration: none;
}
```

