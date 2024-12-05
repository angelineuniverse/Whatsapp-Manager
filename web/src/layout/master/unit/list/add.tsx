import { Component, ReactNode } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../../router/interface";
import { create, store } from "./controller";
import { Button, Checkbox, Form } from "@angelineuniverse/design";
import { mapForm } from "../../../../service/helper";

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
          <div className="block">
            <p className=" font-interbold md:text-lg capitalize">
              Tambah Unit Baru
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
        <div className=" flex flex-row gap-x-3 items-center mt-4">
          <Button
            title="Kembali"
            theme="error"
            size="small"
            width="block"
            onClick={() => {
              this.props.navigate(-1);
            }}
          />
          <Button
            title="Simpan Data"
            theme="primary"
            size="small"
            width="block"
            isDisable={!this.state.check}
            isLoading={this.state.loading}
            onClick={() => {
              this.saved();
            }}
          />
        </div>
      </div>
    );
  }
}

export default withRouterInterface(Add);
