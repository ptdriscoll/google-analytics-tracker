<?php 
  if(isset($_POST['url'])) {
      $url = $_POST['url'];
      header("Location: $url");
      exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Google Analytics tracking page</title>
    <style>
      :root {
        --blue: rgba(54, 162, 235, 0.6);
        --gray: rgba(0, 0, 0, 0.1);
      }
      * {
        box-sizing: border-box;
      }
      html,
      body {
        margin: 0;
        padding: 0;
      }
      body {
        background-color: #f7f9f9;
      }

      /*================================================== 
      Loader
      ==================================================*/
      #loader {
        margin: 50px auto 0;
        border: 1rem solid var(--gray);
        border-top: 1rem solid var(--blue);
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }
    </style>
  </head>
  <body>
    <div id="loader"></div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const queryParams = new URLSearchParams(window.location.search);
        if (queryParams.has('url')) {
          const url = setURL(queryParams); //format final URL, if needed
          loadGoogleTag(url); //callback invokes submitRedirect(url)
        }
      });

      /**
       * Formats URL based on what's in page's query parameters.
       * @param {URLSearchParams object} params - such as from window.location.search.
       * @return {string} - Valid URL.
       */
      function setURL(params) {
        let url = params.get('url');

        //replaces klrn-passport-donation with donation page URL, and adds referrer param if there is one
        if (url === 'klrn-passport-donation') {
          url =
            'https://klrn.secureallegiance.com/klrn/WebModule/Donate.aspx?P=WEBPASS&PAGETYPE=PLG&CHECK=W%2fdAvpGzKANMeHHUySsEIOzWDeZ%2beA1M';
          if (
            params.has('referrer') &&
            params.get('referrer').startsWith('pbsvideo:')
          ) {
            url += `&referrer=${params.get('referrer')}`;
          }
        }

        return url;
      }

      /**
       * Load Google tag, send page_view, and call submitRedirect(url).
       * @param {string} url - https URL to redirect to.
       */
      function loadGoogleTag(url) {
        const script = document.createElement('script');
        script.src = 'https://www.googletagmanager.com/gtag/js?id=G-MVLS2ZX0C5';
        document.head.appendChild(script);

        window.dataLayer = window.dataLayer || [];
        function gtag() {
          dataLayer.push(arguments);
        }

        gtag('js', new Date());

        //prevent default page_view
        gtag('config', 'G-MVLS2ZX0C5', {
          send_page_view: false,
        });

        //manually send page_view, and hook submitRedirect(url) to callback
        gtag('event', 'page_view', {
          event_callback: () => submitRedirect(url),
        });

        //go ahead and redirect if loading GTM took too long
        setTimeout(() => submitRedirect(url), 2000);
      }

      /**
       * Create form and then submit post request with redirect URL.
       * @param {string} url - https URL to redirect to.
       */
      function submitRedirect(url) {
        //create form
        const form = document.createElement('form');
        form.method = 'post';
        form.style.display = 'none';

        //create input
        const input = document.createElement('input');
        input.name = 'url';
        input.value = url;

        //create submit
        const submit = document.createElement('input');
        submit.type = 'submit';

        form.appendChild(input);
        form.appendChild(submit);
        document.body.appendChild(form);

        form.submit();
      }
    </script>
  </body>
</html>
