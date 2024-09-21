import { Component, ReactNode } from "react";
import Form from "../../../component/form/form";
import { FormProps } from "../../../component/form/model";
import { RouterInterface } from "../../../router/interface";
import Button from "../../../component/button/button";
import { form } from "./controller";

class Profile extends Component<RouterInterface> {
  state: Readonly<{
    form: Array<FormProps> | undefined;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      form: undefined,
    };

    this.callForm = this.callForm.bind(this);
  }

  componentDidMount(): void {
    this.callForm();
  }

  callForm() {
    form().then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }

  render(): ReactNode {
    return (
      <div>
        <h1 className="font-interbold text-2xl">My Profile</h1>
        <Form
          className="grid grid-cols-2 gap-6 my-6"
          classNameLoading="grid grid-cols-2 gap-6 my-6"
          form={this.state.form}
          lengthLoading={5}
        />
        <Button
          title="Simpan Perubahan"
          theme="primary"
          size="medium"
          width="block"
          isLoading
        />
      </div>
    );
  }
}

export default Profile;
