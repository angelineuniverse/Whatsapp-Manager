import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { create } from "./controller";
import { Button, Checkbox, Form, Icon } from "@angelineuniverse/design";

class Add extends Component<RouterInterface> {
  state: Readonly<{
    form: undefined;
    check: boolean;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
      check: false,
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
      console.log(value, key);
    }
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
          className="grid grid-cols-4 gap-5 mt-8"
          onSelect={(event, key) =>
            this.onChangeSelect(event.target.value, key)
          }
        />
        <Checkbox
          label="Saya bertanggung jawab dengan informasi di atas ini"
          className="mt-8"
          defaultValue={this.state.check}
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
          isDisable={this.state.check}
        />
      </div>
    );
  }
}

export default withRouterInterface(Add);
