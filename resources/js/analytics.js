// @flow

import nullthrows from 'nullthrows';

declare var ga: any;

const trackingId = document.getElementById('google-analytics-tracking-id');
if (trackingId) {
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        (i[r] =
            i[r] ||
            function () {
                (i[r].q = i[r].q || []).push(arguments);
            }),
            (i[r].l = 1 * new Date());
        (a = s.createElement(o)), (m = s.getElementsByTagName(o)[0]);
        a.async = true;
        a.src = g;
        nullthrows(m.parentNode).insertBefore(a, m);
    })(
        window,
        document,
        'script',
        'https://www.google-analytics.com/analytics.js',
        'ga'
    );

    ga('create', {
        trackingId: trackingId.getAttribute('content'),
        cookieDomain: 'auto',
        siteSpeedSampleRate: 100,
    });
    ga('set', 'transport', 'beacon');
    ga('send', 'pageview');
}
