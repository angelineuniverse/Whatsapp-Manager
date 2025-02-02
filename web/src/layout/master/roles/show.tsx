import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { update, edit } from "./controller";
import { Button, Checkbox, Form, Icon } from "@angelineuniverse/design";
import { mapForm } from "../../../service/helper";

class Show extends Component<RouterInterface> {
  state: Readonly<{
    form: undefined;
    menu: undefined | Array<any>;
    check: boolean;
    selectAll: boolean;
    loading: boolean;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
      menu: undefined,
      check: false,
      selectAll: false,
      loading: false,
    };

    this.callForm = this.callForm.bind(this);
  }

  componentDidMount(): void {
    this.callForm();
  }
  async callForm() {
    await edit(this.props.params?.id).then((res) => {
      const fil = res.data?.data?.menu.filter((a: any) => a.selected === false);
      if (fil.length < 1) this.setState({ selectAll: true });
      this.setState({
        form: res.data?.data?.form,
        menu: res.data?.data?.menu,
      });
    });
  }
  async saved() {
    this.setState({
      loading: true,
    });
    const forms = mapForm(this.state.form, false);
    const menus = this.state.menu?.filter(
      (a) => a.selected === true
    ) as Array<any>;
    const menuForm = Array<any>();
    menus.forEach((item) => {
      const listAction: any[] = [];
      const action = item?.action.filter(
        (a: any) => a.selected === true
      ) as Array<any>;
      action.forEach((ac) => {
        listAction.push(ac.id);
      });
      menuForm.push({
        menu: item.id,
        menu_parent_id: item.parent_id,
        action: listAction,
      });
    });
    await update(this.props.params?.id, {
      ...forms,
      access: menus?.length > 0 ? menuForm : null,
    })
      .then(() => {
        this.setState({
          loading: false,
        });
      })
      .catch((err) => {
        this.setState({
          loading: false,
        });
      });
  }
  render(): ReactNode {
    return (
      <div>
        <div className="flex gap-5 items-center">
          <Icon
            icon="arrow_left"
            className=" cursor-pointer"
            width={30}
            height={30}
            onClick={() => {
              this.props.navigate(-1);
            }}
          />
          <div className="block">
            <p className=" font-interbold md:text-lg">Detail Data Roles</p>
            <p className=" text-sm font-interregular">
              Informasi detail data roles yang dipilih
            </p>
          </div>
        </div>
        <Form
          form={this.state.form}
          classNameLoading="grid grid-cols-4 gap-5 mt-8"
          className="grid grid-cols-4 gap-5 mt-8"
        />
        {this.state.menu && (
          <div className="mt-10 text-xs">
            <div className="header mb-5">
              <p className=" font-intersemibold text-base">Atur Akses Menu</p>
              <p className=" font-interregular">
                Pilih Menu yang dapat dimunculkan pada Roles diatas
              </p>
            </div>
            <div className="flex flex-col gap2">
              <Checkbox
                label="Pilih Semua Menu"
                checked={this.state.selectAll}
                onValueChange={(event: boolean) => {
                  let menus = this.state.menu?.map((a) => ({
                    ...a,
                    selected: event,
                  }));
                  this.setState({
                    selectAll: event,
                    menu: menus,
                  });
                }}
              />
              <div className="mt-3 flex flex-col gap-y-3">
                {this.state.menu?.map((item, index) => (
                  <div key={item.id}>
                    {item.parent_id === null && item.isgroup === true && (
                      <div className=" flex flex-col">
                        <span className=" font-intersemibold text-sm">
                          {item.title}
                        </span>
                        <span className=" font-interregular">
                          {item.description}
                        </span>
                      </div>
                    )}
                    {item.parent_id === null && item.isgroup === false && (
                      <div className="relative">
                        <Checkbox
                          key={item.id}
                          label={item.title}
                          classNameLabel=" font-intersemibold text-[14px]"
                          checked={item.selected}
                          description={item.description}
                          classNameDescription="mt-1"
                          onValueChange={(event: boolean) => {
                            const menus = this.state.menu as Array<any>;
                            menus.forEach((value, i) => {
                              if (value.id === item.id) value.selected = event;
                              if (value.parent_id === item.id)
                                value.selected = event;
                            });
                            if (event === false)
                              this.setState({ selectAll: false });
                            this.setState({
                              menu: menus,
                            });
                          }}
                        />
                        <div className=" flex flex-row gap-x-3 mt-3">
                          {item.action?.map((data: any) => (
                            <Checkbox
                              key={data.id}
                              label={data.action}
                              type="button"
                              classNameLabel="font-intersemibold text-[10px]"
                              checked={data.selected}
                              onValueChange={(event: boolean) => {
                                const menus = this.state.menu as Array<any>;
                                menus.forEach((value, i) => {
                                  if (value.id === item.id)
                                    value?.action.forEach(
                                      (ac: any, j: number) => {
                                        if (ac.id === data.id)
                                          ac.selected = event;
                                      }
                                    );
                                });
                                this.setState({
                                  menu: menus,
                                });
                              }}
                            />
                          ))}
                        </div>
                      </div>
                    )}
                    {item.parent_id != null && (
                      <div className="ml-5 relative">
                        <Checkbox
                          key={item.id}
                          label={item.title}
                          classNameLabel=" font-intersemibold text-[14px]"
                          checked={item.selected}
                          description={item.description}
                          onValueChange={(event: boolean) => {
                            const menus = [...(this.state.menu as Array<any>)];
                            menus[index] = { ...item, selected: event };
                            if (event === false)
                              this.setState({ selectAll: false });
                            this.setState({
                              menu: menus,
                            });
                          }}
                        />
                        <div className=" flex flex-row gap-x-3 mt-3">
                          {item.action?.map((data: any) => (
                            <Checkbox
                              key={data.id}
                              label={data.action}
                              type="button"
                              classNameLabel="font-intersemibold text-[10px]"
                              checked={data.selected}
                              onValueChange={(event: boolean) => {
                                const menus = this.state.menu as Array<any>;
                                menus.forEach((value, i) => {
                                  if (value.id === item.id)
                                    value?.action.forEach(
                                      (ac: any, j: number) => {
                                        if (ac.id === data.id)
                                          ac.selected = event;
                                      }
                                    );
                                });
                                this.setState({
                                  menu: menus,
                                });
                              }}
                            />
                          ))}
                        </div>
                      </div>
                    )}
                  </div>
                ))}
              </div>
            </div>
            <div className="h-0.5 border-b border-gray-200/80 my-8 w-full"></div>
          </div>
        )}
        <Checkbox
          label="Saya bertanggung jawab dengan informasi di atas ini"
          checked={this.state.check}
          onValueChange={(event: boolean) =>
            this.setState({
              check: event,
            })
          }
        />
        <Button
          title="Simpan Perubahan"
          theme="primary"
          size="small"
          width="block"
          className="mt-4"
          isDisable={!this.state.check}
          isLoading={this.state.loading}
          onClick={() => this.saved()}
        />
      </div>
    );
  }
}

export default withRouterInterface(Show);
