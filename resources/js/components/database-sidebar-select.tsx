import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { changeDatabase as changeDB } from '@/routes';
import { type SharedData } from '@/types';
import { databaseModel } from '@/types/resources';
import { router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function DatabaseSidebarSelect(databases: databaseModel[]) {
    const { currentDatabase } = usePage<SharedData>().props;
    const [database, setDatabase] = useState<string>(currentDatabase?.toString() ?? databases[0]?.id?.toString());

    const changeDatabase = (value: string) => {
        setDatabase(value);
        router.flushAll();
        router.get(changeDB(value));
    };

    return (
        <div className="w-full justify-start my-4">
            <Label htmlFor="database">Database</Label>

            <input type="hidden" name="database" value={database} />
            <Select onValueChange={(value) => changeDatabase(value)} defaultValue={database}>
                <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Select a database" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Databases</SelectLabel>
                        {databases.map((database) => (
                            <SelectItem key={database.id} value={database.id.toString()}>
                                {database.name}
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
    );
}
