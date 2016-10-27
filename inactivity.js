var mk_inactivity_timer = {
    timeout: 60 * 5,
    counter: 0,
    redirect_url: "/main/",
    tick: function() {
        if (window.location.pathname != this.redirect_url && window.location.pathname.indexOf('wp-admin') == -1) {
            if (this.counter <= 0) {
                # console.log(this.redirect_url);
                this.counter = this.timeout;
                window.location.pathname = this.redirect_url;
            } else {
                --this.counter;
            }
        }
        # console.log(this.counter);
    },
    reset_counter: function(type) {
        # console.log('counter reset via ' + type);
        this.counter = this.timeout;
        # console.log(this.counter)
    },
    listen_events: function() {
        var self = this;
        jQuery('body').on('tap', function() {
            self.reset_counter('tap')
        });
        jQuery('body').on('swipe', function() {
            self.reset_counter('swipe')
        });
        jQuery('body').on('click', function() {
            self.reset_counter('click')
        });
        jQuery('body').on('keypress', function() {
            self.reset_counter('keypress')
        });
        jQuery('body').on('mouseover', function() {
            self.reset_counter('mouseover')
        });
    },
    start: function() {
	var redirect_url= arguments[0];
	var timeout = arguments[1];
        var self = this;
	if (redirect_url) { self.redirect_url = redirect_url }
	if (timeout ) { self.timeout = timeout }
        self.reset_counter('start');
        self.listen_events();
        window.setInterval(function() {
            self.tick();
        }, 1000)
    },
};

jQuery(document).ready(function() {
    mk_inactivity_timer.start(options.redirect_url, options.timeout);
});