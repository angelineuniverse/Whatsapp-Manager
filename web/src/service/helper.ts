export function mapForm(form: any, useFile: boolean) {
  let forms: any = undefined;
  if (useFile) {
    forms = new FormData();
    for (const item of form) {
      if (!item[item?.key]) continue;
      if (item[item?.key] instanceof File) {
        forms.append(item?.key, item[item?.key]);
        continue;
      }
      // if (item?.type === "month") {
      //   forms.append(item?.key, moment(item[item?.key]).format("YYYY-MM")+'-01');
      //   continue;
      // }
      // if (item?.type === "date") {
      //   forms.append(item?.key, moment(item[item?.key]).format("YYYY-MM-DD"));
      //   continue;
      // }
      if (item[item?.key] instanceof Object) {
        forms.append(item?.key, item[item?.key]?.value);
        continue;
      }
      forms.append(item?.key, item[item?.key]);
    }
  } else {
    forms = {};
    for (const item of form) {
      if (!item[item?.key]) continue;
      if (item[item?.key] instanceof Object) {
        forms[item?.key] = item[item?.key]?.value;
        continue;
      }
      forms[item?.key] = item[item?.key];
    }
  }
  
  return forms;
}