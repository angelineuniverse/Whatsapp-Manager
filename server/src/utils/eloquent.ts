export function eloquentWith(
        arr: Array<any> | object,
        keyParent: string,
        keyChild: string,
        key: string
    ) {
    let output: any = null;
    if (arr instanceof Array) {
        return arr.filter((y) => y[keyChild] == null || y[keyChild]?.length < 1).map((item) => ({
            ...item,
            [key]: arr.filter((x) => x[keyChild] == item[keyParent])?.length > 0
                ? arr.filter((x) => x[keyChild] == item[keyParent])
                : null 
        }))
    }
    return output;
}

export function eloquentWithManual(arr: object, key: string, child: Array<any> | object) {
    const output = {
        ...arr,
        [key]: child
    } 
    return output;
}