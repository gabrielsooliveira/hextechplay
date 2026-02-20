import { ref, onMounted, onUnmounted, computed } from 'vue';

/**
 * Tracks window size and provides a mobile breakpoint helper.
 */
export function useWindowSize() {
    const width = ref(window.innerWidth);
    const height = ref(window.innerHeight);

    const update = () => {
        width.value = window.innerWidth;
        height.value = window.innerHeight;
    };

    onMounted(() => {
        window.addEventListener('resize', update);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', update);
    });

    const isMobile = computed(() => width.value < 992);
    const isTablet = computed(() => width.value >= 768 && width.value < 992);
    const isDesktop = computed(() => width.value >= 992);

    return { 
        width, 
        height, 
        isMobile,
        isTablet,
        isDesktop
    };
}
