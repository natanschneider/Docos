import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { changeTable as changeTab, changeDatabase as changeDB } from '@/routes';
import { type SharedData } from '@/types';
import { databaseModel, tableModel } from '@/types/resources';
import { router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function ColumnSidebarSelect({
    databases,
    tables
}: {
    databases: databaseModel[];
    tables: tableModel[];
}) {
    const { currentDatabase, currentTable } = usePage<SharedData>().props;
    const [database, setDatabase] = useState<string>(currentDatabase?.toString() ?? databases[0]?.id?.toString());
    const [table, setTable] = useState<string>(currentTable?.toString() ?? tables[0]?.id?.toString());

    const changeDatabase = (value: string) => {
        setDatabase(value);
        router.flushAll();
        if (table && table !== null && table !== undefined) {
            router.get(changeTab({ database: value, table: table }));
        } else {
            router.get(changeDB(value));
        }
    };

    const changeTable = (value: string) => {
        setTable(value);
        router.flushAll();
        router.get(changeTab({ database: database, table: value }));
    };

    return (
        <div className='w-full my-4'>
            <div className="justify-start">
                <Label htmlFor="database">Database</Label>

                <input type="hidden" name="database" value={database} />
                <Select onValueChange={(value) => changeDatabase(value)} defaultValue={database}>
                    <SelectTrigger className="w-45">
                        <SelectValue placeholder="Select a database" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Database</SelectLabel>
                            {databases.map((database) => (
                                <SelectItem key={database.id} value={database.id.toString()}>
                                    {database.name}
                                </SelectItem>
                            ))}
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>

            <div className="justify-start">
                <Label htmlFor="table">Table</Label>

                <input type="hidden" name="table" value={table} />
                <Select onValueChange={(value) => changeTable(value)} defaultValue={table}>
                    <SelectTrigger className="w-45">
                        <SelectValue placeholder="Select an table" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Table</SelectLabel>
                            {tables.map((table) => (
                                <SelectItem key={table.id} value={table.id.toString()}>
                                    {table.name}
                                </SelectItem>
                            ))}
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>
        </div>
    );
}
