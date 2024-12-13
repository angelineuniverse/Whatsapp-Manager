import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { create, store } from "./controller";
import { show } from "../roles/controller";
import { Button, Checkbox, Form, Icon } from "@angelineuniverse/design";
import { mapForm } from "../../../service/helper";

class Add extends Component<RouterInterface> {
  state: Readonly<{
    form: undefined | Array<any>;
    check: boolean;
    loading: boolean;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
      check: false,
      loading: false,
    };

    this.callForm = this.callForm.bind(this);
  }

  componentDidMount(): void {
    this.callForm();
  }
  async callForm() {
    await create().then((res) => {
      this.setState({
        form: res.data?.data,
      });
    });
  }
  async onChangeSelect(value: any, key: string) {
    if (key === "m_project_tabs_id") {
      await show(value).then(async (res) => {
        const newState = await this.state.form!.map((obj: any) => {
          if (obj.key === "m_roles_tabs_id") {
            return {
              ...obj,
              m_roles_tabs_id: null,
              readonly: false,
              list: {
                ...obj.list,
                options: res.data.data,
              },
            };
          }
          return obj;
        });
        this.setState({
          form: newState,
        });
      });
    }
  }
  async saved() {
    this.setState({
      loading: true,
    });
    const form = mapForm(this.state.form, true);
    store(form)
      .then((res) => {
        console.log(res);
        this.setState({
          loading: false,
          form: undefined,
        });
        this.callForm();
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
            <p className=" font-interbold md:text-lg capitalize">
              Tambah pengguna baru
            </p>
            <p className=" text-sm font-interregular">
              Harap lengkapi form yang tersedia dibawah ini
            </p>
          </div>
        </div>
        <Form
          form={this.state.form}
          classNameLoading="grid grid-cols-4 gap-5 mt-8"
          className="grid grid-cols-4 gap-5 mt-8 mb-8"
          onSelect={(event, key) =>
            this.onChangeSelect(event.target.value, key)
          }
        />
        <Suspense>
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
            title="Simpan Data"
            theme="primary"
            size="small"
            width="block"
            className="mt-4"
            isDisable={!this.state.check}
            isLoading={this.state.loading}
            onClick={() => {
              this.saved();
            }}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouterInterface(Add);
