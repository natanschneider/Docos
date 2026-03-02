import { type NavItem } from '@/types/resources.d';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    currentCompany: number | null;
    currentProject: number | null;
    currentApplication: number | null;
    currentDatabase: number | null;
    currentTable: number | null;
    companies: { id: number; name: string; public_key: string }[];
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface UpdateItem {
    id: string;
    name: string;
    updated_at: string;
}

export interface Category {
    id: string;
    name: string;
    items: UpdateItem[];
}
