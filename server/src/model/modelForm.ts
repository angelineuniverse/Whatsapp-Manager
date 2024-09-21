export interface ModelForm{
    key?: string;
    type: string;
    label: string;
    isRequired: boolean;
    readonly?: boolean;
    description?: string;
    placeholder: string;
    autosize: boolean;
    // list?: ListProps;
    children_custom?: any;
}