import { appearance } from '@/routes';
import application from '@/routes/application';
import column from '@/routes/column';
import company from '@/routes/company';
import database from '@/routes/database';
import endpoint from '@/routes/endpoint';
import { edit as editPassword } from '@/routes/password';
import { edit } from '@/routes/profile';
import project from '@/routes/project';
import screen from '@/routes/screen';
import table from '@/routes/table';
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
    databases: databaseModel[] | null;
    projects: projectModel[] | null;
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
    screens: screenModel[] | null;
    endpoints: endpointModel[] | null;
    project: projectModel | null;
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

export interface screenModel {
    id: number;
    name: string;
    application_id: number;
    created_at: string;
    updated_at: string;
    public_key: string;
    columns: columnModel[];
    application: applicationModel | null;
};

export const ScreenNavItems: NavItem[] = [
    {
        title: 'Create',
        href: screen.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: screen.index().url,
        icon: null,
    },
];

export interface endpointModel {
    id: number;
    name: string;
    application_id: number;
    created_at: string;
    updated_at: string;
    public_key: string;
    columns: columnModel[];
    application: applicationModel | null;
};

export const EndpointNavItems: NavItem[] = [
    {
        title: 'Create',
        href: endpoint.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: endpoint.index().url,
        icon: null,
    },
];

export interface tableModel {
    id: number;
    name: string;
    doc_file: string;
    database_id: number;
    created_at: string;
    updated_at: string;
    public_key: string;
    columns: columnModel[] | null;
};

export const TableNavItems: NavItem[] = [
    {
        title: 'Create',
        href: table.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: table.index().url,
        icon: null,
    },
];

export interface columnModel {
    id: number;
    name: string;
    doc_file: string;
    table_id: number;
    type_id: number;
    created_at: string;
    updated_at: string;
    public_key: string;
    type: typeModel | null;
    index: indexModel | null;
    constraints: constraintsModel[] | null;
    related_pks: columnModel[] | null;
    related_fks: columnModel[] | null;
    table: tableModel | null;
    endpoints: endpointModel[] | null;
    screens: screenModel[] | null;
    index: indexModel | null;
};

export const ColumnNavItems: NavItem[] = [
    {
        title: 'Create',
        href: column.create().url,
        icon: null,
    },
    {
        title: 'List',
        href: column.index().url,
        icon: null,
    },
];

export interface typeModel {
    id: number;
    name: string;
}

export interface indexModel {
    id: number;
    column_id: number;
}

export interface constraintsModel {
    id: number;
    name: string;
    pivot: {
        column_id: number;
        constraint_id: number;
    }
}
