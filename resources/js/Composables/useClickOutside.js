import { onMounted, onUnmounted } from 'vue';

/**
 * Detects clicks outside a specific element.
 * @param {import('vue').Ref} elRef - The reference to the element to watch.
 * @param {Function} callback - The function to call when a click outside occurs.
 * @param {import('vue').Ref[]} excludes - Optional references to other elements to exclude from triggering the callback.
 */
export function useClickOutside(elRef, callback, excludes = []) {
    if (!elRef) return;

    const listener = (event) => {
        if (!elRef.value || elRef.value.contains(event.target)) {
            return;
        }

        // Check excludes
        if (excludes.length > 0) {
            const isExcluded = excludes.some(exclude => exclude.value && exclude.value.contains(event.target));
            if (isExcluded) return;
        }

        callback(event);
    };

    onMounted(() => {
        document.addEventListener('click', listener);
    });

    onUnmounted(() => {
        document.removeEventListener('click', listener);
    });
}
