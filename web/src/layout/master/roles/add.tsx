import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { add, create } from "./controller";
import { Button, Checkbox, Form, Icon } from "@angelineuniverse/design";
import { mapForm } from "../../../service/helper";

class Add extends Component<RouterInterface> {
  state: Readonly<{
    form: undefined;
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
  async saved() {
    this.setState({
      loading: true,
    });
    const forms = mapForm(this.state.form, false);
    await add(forms)
      .then(() => {
        this.setState({
          loading: false,
          form: undefined,
          check: false,
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
            <p className=" font-interbold md:text-lg">Tambah data Roles</p>
            <p className=" text-sm font-interregular">
              Harap lengkapi form yang tersedia dibawah ini
            </p>
          </div>
        </div>
        <Form
          form={this.state.form}
          classNameLoading="grid grid-cols-4 gap-5 mt-8"
          className="grid grid-cols-4 gap-5 mt-8"
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
          isDisable={!this.state.check}
          isLoading={this.state.loading}
          onClick={() => this.saved()}
        />
      </div>
    );
  }
}

export default withRouterInterface(Add);
