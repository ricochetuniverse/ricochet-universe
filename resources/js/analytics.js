// @flow

const trackingId = document
    .getElementById('google-analytics-tracking-id')
    ?.getAttribute('content');

if (trackingId != null && trackingId !== '') {
    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + trackingId;
    document.body?.appendChild(script);

    window.dataLayer = window.dataLayer || [];
    // eslint-disable-next-line no-inner-declarations
    function gtag() {
        window.dataLayer.push(arguments);
    }
    // $FlowFixMe[extra-arg]
    gtag('js', new Date());

    // $FlowFixMe[extra-arg]
    gtag('config', trackingId);
}
