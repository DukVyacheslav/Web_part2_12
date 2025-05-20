class Analytics {
    constructor() {
        this.sessionStartTime = Date.now();
        this.initializeSession();
        this.setupEventListeners();
    }

    initializeSession() {
        if (!localStorage.getItem('sessionId')) {
            localStorage.setItem('sessionId', this.generateSessionId());
        }
        this.trackPageView();
    }

    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    setupEventListeners() {
        // Отслеживание кликов по элементам
        document.addEventListener('click', (e) => {
            const target = e.target.closest('a, button, .photo-item, .card');
            if (target) {
                this.trackEvent('click', {
                    element: target.tagName.toLowerCase(),
                    class: target.className,
                    id: target.id,
                    text: target.textContent?.trim()
                });
            }
        });

        // Отслеживание отправки форм
        document.addEventListener('submit', (e) => {
            if (e.target.tagName === 'FORM') {
                this.trackEvent('form_submit', {
                    formId: e.target.id || 'unnamed_form',
                    formAction: e.target.action
                });
            }
        });

        // Отслеживание прокрутки
        let lastScrollPosition = 0;
        let scrollTimer;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                const newPosition = window.scrollY;
                const scrollDepth = Math.round((newPosition / (document.documentElement.scrollHeight - window.innerHeight)) * 100);
                if (Math.abs(newPosition - lastScrollPosition) > 100) {
                    this.trackEvent('scroll_depth', { depth: scrollDepth });
                    lastScrollPosition = newPosition;
                }
            }, 100);
        });

        // Отслеживание времени на странице при уходе
        window.addEventListener('beforeunload', () => {
            const timeSpent = Math.round((Date.now() - this.sessionStartTime) / 1000);
            this.trackEvent('page_exit', { timeSpent });
        });
    }

    trackPageView() {
        const pageData = {
            url: window.location.pathname,
            title: document.title,
            timestamp: new Date().toISOString(),
            referrer: document.referrer
        };

        this.saveToStorage('pageViews', pageData);
    }

    trackEvent(eventName, eventData) {
        const event = {
            name: eventName,
            data: eventData,
            timestamp: new Date().toISOString(),
            url: window.location.pathname,
            sessionId: localStorage.getItem('sessionId')
        };

        this.saveToStorage('events', event);
    }

    saveToStorage(key, data) {
        try {
            let items = JSON.parse(localStorage.getItem(key) || '[]');
            items.push(data);

            // Ограничиваем количество хранимых элементов
            if (items.length > 1000) {
                items = items.slice(-1000);
            }

            localStorage.setItem(key, JSON.stringify(items));
            
            // Также сохраняем в куки для долгосрочного хранения
            this.setCookie(key, JSON.stringify(items.slice(-10)), 365);
        } catch (e) {
            console.error('Error saving analytics data:', e);
        }
    }

    setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${encodeURIComponent(value)};expires=${date.toUTCString()};path=/`;
    }

    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1);
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
        }
        return null;
    }

    getStatistics() {
        try {
            const pageViews = JSON.parse(localStorage.getItem('pageViews') || '[]');
            const events = JSON.parse(localStorage.getItem('events') || '[]');

            const stats = {
                totalPageViews: pageViews.length,
                uniquePages: new Set(pageViews.map(p => p.url)).size,
                mostViewedPages: this.getMostViewedPages(pageViews),
                popularEvents: this.getPopularEvents(events),
                averageTimeOnSite: this.calculateAverageTimeOnSite(events)
            };

            return stats;
        } catch (e) {
            console.error('Error calculating statistics:', e);
            return null;
        }
    }

    getMostViewedPages(pageViews) {
        const pages = {};
        pageViews.forEach(view => {
            pages[view.url] = (pages[view.url] || 0) + 1;
        });
        return Object.entries(pages)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 5);
    }

    getPopularEvents(events) {
        const eventCounts = {};
        events.forEach(event => {
            eventCounts[event.name] = (eventCounts[event.name] || 0) + 1;
        });
        return Object.entries(eventCounts)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 5);
    }

    calculateAverageTimeOnSite(events) {
        const exitEvents = events.filter(e => e.name === 'page_exit');
        if (exitEvents.length === 0) return 0;
        
        const totalTime = exitEvents.reduce((sum, event) => sum + event.data.timeSpent, 0);
        return Math.round(totalTime / exitEvents.length);
    }
}

// Инициализация аналитики
const analytics = new Analytics();