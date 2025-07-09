declare global {
    interface Window {
        dataLayer: unknown[];
    }
}

const trackingId = document
    .getElementById('google-analytics-tracking-id')
    ?.getAttribute('content');

if (trackingId != null && trackingId !== '') {
    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + trackingId;
    document.body.appendChild(script);

    window.dataLayer = window.dataLayer || [];
    function gtag() {
        // eslint-disable-next-line prefer-rest-params
        window.dataLayer.push(arguments);
    }
    // @ts-expect-error 3rd party library
    gtag('js', new Date());

    // @ts-expect-error 3rd party library
    gtag('config', trackingId);
}
