import { AppPageProps } from '@/types/index';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}

// NativePHP Mobile bridge
declare module '#nativephp' {
    export function on(event: string, callback: (...args: unknown[]) => void): void;
    export function off(event: string, callback: (...args: unknown[]) => void): void;

    export const Events: {
        Biometric: {
            Completed: string;
        };
        Alert: {
            ButtonPressed: string;
        };
        Camera: {
            PhotoTaken: string;
        };
        [key: string]: Record<string, string>;
    };

    export const SecureStorage: {
        get(key: string): Promise<string | null>;
        set(key: string, value: string): Promise<boolean>;
        delete(key: string): Promise<boolean>;
    };

    export const Biometrics: {
        prompt(): Promise<void>;
    };

    export const Dialog: {
        alert(title: string, message: string, buttons?: string[]): Promise<void>;
        toast(message: string): Promise<void>;
    };

    export const Browser: {
        open(url: string): Promise<void>;
        inApp(url: string): Promise<void>;
        auth(url: string): Promise<void>;
    };

    export const Camera: {
        getPhoto(): Promise<void>;
        recordVideo(): Promise<void>;
        pickImages(): Promise<void>;
    };

    export const Microphone: {
        record(): Promise<void>;
    };
}

// Global NativePHP call function
declare global {
    interface Window {
        nativephp_call?: (method: string, params: string) => void;
    }
}
