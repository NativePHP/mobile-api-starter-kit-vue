const API_URL = import.meta.env.VITE_API_URL || '';

export function apiUrl(path: string): string {
    const baseUrl = API_URL.replace(/\/$/, '');
    const cleanPath = path.startsWith('/') ? path : `/${path}`;
    return `${baseUrl}${cleanPath}`;
}

interface ApiRequestOptions extends Omit<RequestInit, 'body'> {
    body?: Record<string, unknown>;
    token?: string;
}

export async function apiFetch<T = unknown>(
    path: string,
    options: ApiRequestOptions = {},
): Promise<{ response: Response; data: T }> {
    const { body, token, headers: customHeaders, ...fetchOptions } = options;

    const headers: Record<string, string> = {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        ...(customHeaders as Record<string, string>),
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const response = await fetch(apiUrl(path), {
        ...fetchOptions,
        headers,
        body: body ? JSON.stringify(body) : undefined,
    });

    const data = (await response.json()) as T;

    return { response, data };
}