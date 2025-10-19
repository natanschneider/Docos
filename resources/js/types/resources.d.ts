import { appearance } from '@/routes';
import { edit as editPassword } from '@/routes/password';
import { edit } from '@/routes/profile';
import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';
import { type ReactNode } from 'react';
import company from '@/routes/company';

export interface companyModel {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
    public_key: string;
}

export interface ResourcesLayoutProps {
    sidebarNavItems: NavItem[];
    title: string;
    description: string;
    children: ReactNode;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export const SettingsNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: edit(),
        icon: null,
    },
    {
        title: 'Password',
        href: editPassword(),
        icon: null,
    },
    {
        title: 'Appearance',
        href: appearance(),
        icon: null,
    },
];

export const CompanyNavItems: NavItem[] = [
    {
        title: 'Create',
        href: company.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: company.index().url,
        icon: null
    }
];
