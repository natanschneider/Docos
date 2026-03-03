import {
    DatabaseSchemaNode,
    DatabaseSchemaNodeBody,
    DatabaseSchemaNodeHeader,
    DatabaseSchemaTableCell,
    DatabaseSchemaTableRow,
} from '@/components/database-schema-node';
import { LabeledHandle } from '@/components/labeled-handle';
import { constraintsModel, tableModel } from '@/types/resources';
import { Position, Background, Edge, ReactFlow } from '@xyflow/react';
import { memo } from 'react';
import '@xyflow/react/dist/style.css';
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger, DropdownMenuLabel } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { EyeIcon, MoreHorizontalIcon, PencilIcon } from 'lucide-react';
import { Label } from '@/components/ui/label';
import column from '@/routes/column';
import { Link } from '@inertiajs/react';

export type DatabaseSchemaNodeData = {
    data: {
        label: string;
        schema: {
            title: string;
            type: string,
            id: number,
            contraints: constraintsModel[] | null
        }[];
    };
};

export const DatabaseSchema = memo(({ data }: DatabaseSchemaNodeData) => {
    return (
        <DatabaseSchemaNode className="p-0">
            <DatabaseSchemaNodeHeader>{data.label}</DatabaseSchemaNodeHeader>
            <DatabaseSchemaNodeBody>
                {data.schema.map((entry) => (
                    <DatabaseSchemaTableRow key={entry.title}>
                        <DatabaseSchemaTableCell className="pr-6 pl-0 font-light">
                            <LabeledHandle id={entry.title} title={entry.title} type="target" position={Position.Left} />
                        </DatabaseSchemaTableCell>
                        <DatabaseSchemaTableCell className="pr-0 font-thin">
                            <Label>{entry.type}</Label>
                        </DatabaseSchemaTableCell>
                        <DatabaseSchemaTableCell className="text-right">
                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <Button variant="ghost" size="icon" className="size-8">
                                        <MoreHorizontalIcon />
                                        <span className="sr-only">Open menu</span>
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuGroup>
                                        <DropdownMenuItem asChild>
                                            <Link href={column.show(entry.id)} className='flex items-center gap-1'>
                                                <EyeIcon className='w-4 h-4' />
                                                Visualize
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuItem asChild>
                                            <Link href={column.edit(entry.id)} className='flex items-center gap-1'>
                                                <PencilIcon className='w-4 h-4' />
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>
                                    </DropdownMenuGroup>
                                    {entry.contraints && entry.contraints.length > 0 && (
                                        <>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuGroup>
                                                <DropdownMenuLabel className="font-extralight">Contrainsts</DropdownMenuLabel>
                                                {entry.contraints.map((constraint) => (
                                                    <DropdownMenuItem key={constraint.id}>{constraint.name}</DropdownMenuItem>
                                                ))}
                                            </DropdownMenuGroup>
                                        </>
                                    )}
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </DatabaseSchemaTableCell>
                        <DatabaseSchemaTableCell className="pr-0 font-thin">
                            <LabeledHandle
                                id={entry.title}
                                title=''
                                type="source"
                                position={Position.Right}
                                className="p-0"
                                handleClassName="p-0"
                                labelClassName="p-0 w-full pr-3 text-right"
                            />
                        </DatabaseSchemaTableCell>
                    </DatabaseSchemaTableRow>
                ))}
            </DatabaseSchemaNodeBody>
        </DatabaseSchemaNode>
    );
});

const nodeTypes = {
    databaseSchema: DatabaseSchema,
};

export default function Diagram({ tables }: { tables: tableModel[] }) {
    const defaultNodes = [];
    const defaultEdges: Edge[] = [];

    const cols = 3;
    let index = 0;
    let x = 0;
    let y = 0;
    for (const table of tables) {
        index++;

        x = (index % cols) * 300;
        y = Math.floor(index / cols) * 300;
        defaultNodes.push({
            id: table.id.toString(),
            type: 'databaseSchema',
            data: {
                label: table.name,
                schema: table?.columns?.map((column) => ({
                    title: column.name,
                    type: column.type?.name,
                    id: column.id,
                    contraints: column?.constraints
                })),
            },
            position: { x: x, y: y },
        });

        if (table?.columns) {
            for (const col of table.columns) {
                if (col?.related_fks) {
                    for (const fk of col.related_fks) {
                        defaultEdges.push({
                            id: `${col.id}-${fk.id}`,
                            source: table.id.toString(),
                            target: fk.table_id.toString(),
                            sourceHandle: col.name,
                            targetHandle: fk.name
                        });
                    }
                }
            }
        }
    }

    return (
        <div className='w-5xl h-[40rem]'>
            <ReactFlow
                defaultNodes={defaultNodes}
                defaultEdges={defaultEdges}
                nodeTypes={nodeTypes}
                fitView
            >
                <Background />
            </ReactFlow>
        </div>
    )
}
