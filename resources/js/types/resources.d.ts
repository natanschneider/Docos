import { appearance } from '@/routes';
import application from '@/routes/application';
import company from '@/routes/company';
import database from '@/routes/database';
import { edit as editPassword } from '@/routes/password';
import { edit } from '@/routes/profile';
import project from '@/routes/project';
import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';
import { type ReactNode } from 'react';

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
    sidebarExtraNavItems?: ReactNode;
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
        icon: null,
    },
];

export interface projectModel {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
    public_key: string;
}

export const ProjectNavItems: NavItem[] = [
    {
        title: 'Create',
        href: project.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: project.index().url,
        icon: null,
    },
];

export interface databaseModel {
    id: number;
    name: string;
    engine_id: number;
    engine: engineModel;
    created_at: string;
    updated_at: string;
    public_key: string;
}

export const DatabaseNavItems: NavItem[] = [
    {
        title: 'Create',
        href: database.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: database.index().url,
        icon: null,
    },
];

export interface engineModel {
    id: number;
    name: string;
}

export interface applicationModel {
    id: number;
    name: string;
    project_id: number;
    created_at: string;
    updated_at: string;
    public_key: string;
    databases: databaseModel[];
}

export const ApplicationNavItems: NavItem[] = [
    {
        title: 'Create',
        href: application.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: application.index().url,
        icon: null,
    },
];
